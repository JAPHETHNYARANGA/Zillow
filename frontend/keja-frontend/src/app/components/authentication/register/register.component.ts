import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { Router } from '@angular/router';

import AOS from 'aos';
import { AuthenticationService } from 'src/app/services/authentication/authentication.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.scss']
})
export class RegisterComponent implements OnInit {
  registerForm!: FormGroup;
  isLoading = false;
  showNotification = false;
  notificationMessage = '';
  notificationType: 'success' | 'error' = 'success';

  constructor(
    private fb: FormBuilder, 
    private router: Router, 
    private authService: AuthenticationService
  ) {}

  ngOnInit(): void {
    AOS.init();
    this.registerForm = this.fb.group({
      name: ['', [Validators.required, Validators.maxLength(255)]],
      email: ['', [Validators.required, Validators.email]],
      password: ['', [Validators.required, Validators.minLength(6)]],
      confirmPassword: ['', [Validators.required]],
      role: ['', [Validators.required]]
    }, {
      validator: this.passwordMatchValidator
    });
  }

  passwordMatchValidator(formGroup: FormGroup): any {
    const password = formGroup.get('password')?.value;
    const confirmPassword = formGroup.get('confirmPassword')?.value;
    return password === confirmPassword ? null : { passwordMismatch: true };
  }

  showNotificationMessage(message: string, type: 'success' | 'error') {
    this.notificationMessage = message;
    this.notificationType = type;
    this.showNotification = true;
    setTimeout(() => {
      this.showNotification = false;
    }, 5000);
  }

  onSubmit(): void {
    if (this.registerForm.invalid) {
      this.showNotificationMessage('Please fill in all fields correctly', 'error');
      return;
    }

    this.isLoading = true;
    
    this.authService.register(this.registerForm.value).subscribe({
      next: (response: any) => {
        this.isLoading = false;
        this.showNotificationMessage('Registration successful! Redirecting...', 'success');
        setTimeout(() => {
          this.router.navigate(['']);
        }, 1500);
      },
      error: (error: any) => {
        this.isLoading = false;
        const errorMessage = error.error?.message || 'Registration failed. Please try again.';
        this.showNotificationMessage(errorMessage, 'error');
        console.error('Registration error', error);
      }
    });
  }

  navigateToLogin() {
    this.router.navigate(['login']);
  }
}