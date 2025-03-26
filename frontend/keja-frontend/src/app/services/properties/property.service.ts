import { HttpClient, HttpHeaders, HttpErrorResponse } from '@angular/common/http';
import { Injectable } from '@angular/core';
import { Observable, throwError } from 'rxjs';
import { catchError } from 'rxjs/operators';

@Injectable({
  providedIn: 'root'
})
export class PropertyService {
  private apiUrl = 'http://127.0.0.1:8000/api'; // Base API URL

  constructor(private http: HttpClient) { }

  // Get all properties with pagination
  getProperties(page: number = 1, perPage: number = 10): Observable<any> {
    return this.http.get(`${this.apiUrl}/fetch_properties?page=${page}&per_page=${perPage}`)
      .pipe(
        catchError(this.handleError)
      );
  }

  // Get single property by ID
  getPropertyById(id: number): Observable<any> {
    return this.http.get(`${this.apiUrl}/fetch_property/${id}`)
      .pipe(
        catchError(this.handleError)
      );
  }

  // Create property
  createProperty(formData: FormData): Observable<any> {
    
    const token = localStorage.getItem('auth_token');
    if (!token) {
      return throwError('Authentication token not found');
    }

    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    return this.http.post(`${this.apiUrl}/properties`, formData, { headers })
      .pipe(
        catchError(this.handleError)
      );
  }

  // Update property
  updateProperty(id: number, formData: FormData): Observable<any> {
    const token = localStorage.getItem('auth_token');
    if (!token) {
      return throwError('Authentication token not found');
    }

    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    return this.http.post(`${this.apiUrl}/properties/${id}`, formData, { headers })
      .pipe(
        catchError(this.handleError)
      );
  }

  // Delete property
  deleteProperty(id: number): Observable<any> {
    const token = localStorage.getItem('auth_token');
    if (!token) {
      return throwError('Authentication token not found');
    }

    const headers = new HttpHeaders({
      'Authorization': `Bearer ${token}`
    });

    return this.http.delete(`${this.apiUrl}/properties/${id}`, { headers })
      .pipe(
        catchError(this.handleError)
      );
  }

  // Handle API errors
  private handleError(error: HttpErrorResponse) {
    if (error.error instanceof ErrorEvent) {
      // Client-side error
      console.error('An error occurred:', error.error.message);
      return throwError('Something went wrong. Please try again later.');
    } else {
      // Server-side error
      console.error(
        `Backend returned code ${error.status}, ` +
        `body was: ${JSON.stringify(error.error)}`);

      // Customize error messages based on status code
      if (error.status === 0) {
        return throwError('Could not connect to the server. Please check your internet connection.');
      } else if (error.status === 401) {
        return throwError('Unauthorized - Please login again.');
      } else if (error.status === 403) {
        return throwError('Forbidden - You don\'t have permission to perform this action.');
      } else if (error.status === 404) {
        return throwError('Resource not found.');
      } else if (error.status === 422) {
        // Handle Laravel validation errors
        const errors = error.error.errors;
        const errorMessages = Object.values(errors).flat().join('\n');
        return throwError(errorMessages);
      } else if (error.status >= 500) {
        return throwError('Server error - Please try again later.');
      }

      return throwError('Something went wrong. Please try again.');
    }
  }

  // Helper to get full image URL
  getImageUrl(imagePath: string): string {
    if (!imagePath) return 'assets/property.svg';
    
    // Check if already a full URL
    if (imagePath.startsWith('http')) return imagePath;
    
    // Handle different image path formats
    if (imagePath.includes('storage/properties/')) {
      return `${this.apiUrl}/${imagePath}`;
    }
    
    return `${this.apiUrl}/storage/properties/${imagePath}`;
  }
}