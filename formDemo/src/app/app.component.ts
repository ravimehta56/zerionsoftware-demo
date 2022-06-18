import { Component, OnInit, ViewChild } from '@angular/core';
import { FormGroup, FormBuilder, Validators, NgForm } from '@angular/forms';
import { MatTableDataSource } from '@angular/material/table';
import { ApiServiceService } from './services/api-service.service';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent implements OnInit {
  // Initialize required variables
  myForm: FormGroup;
  list: MatTableDataSource<any>;
  displayedColumns: string[] = ['first_name', 'last_name', 'email_address', 'mobile_number'];
  @ViewChild('table', { static: true }) table: any;
  @ViewChild('formDir', { static: true }) formDir: NgForm;
  constructor(
    public fb: FormBuilder,
    private apiServices: ApiServiceService
  ) { }

  ngOnInit(): void {
    // Initialize form
    this.reactiveForm();
    // Load data Initially
    this.listData();
  }

  /* Reactive form */
  reactiveForm = () => {
    this.myForm = this.fb.group({
      first_name: ['', [Validators.required]],
      last_name: ['', [Validators.required]],
      email_address: ['', [Validators.required, Validators.email]],
      mobile_number: ['', [Validators.required, Validators.pattern(/^[0-9]*$/), Validators.minLength(7)]],
      password: ['', [Validators.required, Validators.minLength(6)]],
      confirm_password: [''],
    }, {
      // here we attach our form validator
      validators: controlsEqual('confirm_password', 'password')
    });
  }

  // List Data method to retrieve list of records
  listData = () => {
    this.apiServices.postRequest('get-api.php', []).then((res: any) => {
      if (res && res.status) {
        this.list = res.data;
      }
    });
  }

  // Error Handling method to trigger validation messages
  public errorHandling = (control: string, error: string) => {
    return this.myForm.controls[control].hasError(error);
  }

  // Submit form 
  submitForm = () => {
    this.apiServices.postRequest('server-api.php', this.myForm.value).then((res: any) => {
      if (res && res.status && res.data) {
        this.formDir.resetForm(this.myForm);
        this.listData();
      }
    });
  }
}

// Validation Function
export function controlsEqual(
  controlName: string,
  equalToName: string,
  errorKey: string = controlName // here you can customize validation error key
) {
  return (form: FormGroup) => {
    const control = form.get(controlName);
    if (control.value !== form.get(equalToName).value) {
      control.setErrors({ notMatched: true });
      return {
        [errorKey]: true
      };
    } else {
      control.setErrors(null);
      return null;
    }
  };
}
