import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';
import { tap } from 'rxjs/operators'; // Import tap operator for side effects

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
    return this.http.post('http://127.0.0.1:8000/api/login', { email, password }).pipe(
      // Store token on login success
      tap((response: any) => {
        if (response && response.access_token) {
          this.storeToken(response.access_token);  // Store the access token
        }
      })
    );
  }

  // Forgot password request
  forgotPassword(email: string): Observable<any> {
    return this.http.post('http://127.0.0.1:8000/api/forgot-password', { email }); // Replace with your API endpoint
  }

  // Store auth token in localStorage
  private storeToken(token: string): void {
    localStorage.setItem('auth_token', token);  // Store the access token in localStorage
  }

  // Get auth token from localStorage
  getAuthToken(): string | null {
    return localStorage.getItem('auth_token');  // Retrieve the access token from localStorage
  }

  // Remove auth token on logout
  logout(): void {
    localStorage.removeItem('auth_token');  // Remove the token from localStorage
  }
}
