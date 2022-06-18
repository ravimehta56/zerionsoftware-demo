import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders, HttpParams } from '@angular/common/http';
@Injectable({
  providedIn: 'root'
})
export class ApiServiceService {
  PHP_API_SERVER = 'http://localhost/zerionsoftware-demo/';
  constructor(
    private httpClient: HttpClient
  ) { }

  public postRequest(url, formData: any) {
    const fullUrl = this.PHP_API_SERVER + url;
    const headers = new HttpHeaders({ Accept: 'application/json' });

    let params = new HttpParams();
    Object.keys(formData).forEach((key) => {
      params = params.append(key, formData[key]);
    });
    return new Promise((resolve, reject) => {
      this.httpClient.post(fullUrl, params, { headers })
        .subscribe(data => {
          resolve(data);
        }, err => {
          reject(err);
        });
    });
  }

  public getRequest(url) {
    const fullUrl = this.PHP_API_SERVER + url;
    const headers = new HttpHeaders({ Accept: 'application/json' });
    return new Promise((resolve, reject) => {
      this.httpClient.get(fullUrl, { headers })
        .subscribe(data => {
          resolve(data);
        }, err => {
          reject(err);
        });
    });
  }
}
