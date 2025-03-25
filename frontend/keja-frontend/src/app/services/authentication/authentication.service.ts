import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class AuthenticationService {

  constructor(private http: HttpClient) { }

  // Register user
  register(userData: any): Observable<any> {
    return this.http.post('http://127.0.0.1:8000/api/register', userData); // Replace with your API endpoint
  }

  // Login user
  login(email: string, password: string): Observable<any> {
    return this.http.post('http://127.0.0.1:8000/api/login', { email, password }); // Replace with your API endpoint
  }

  // Forgot password request
  forgotPassword(email: string): Observable<any> {
    return this.http.post('http://127.0.0.1:8000/api/forgot-password', { email }); // Replace with your API endpoint
  }
}
