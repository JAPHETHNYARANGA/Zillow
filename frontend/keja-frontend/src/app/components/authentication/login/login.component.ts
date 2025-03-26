import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import AOS from 'aos';
import { AuthenticationService } from 'src/app/services/authentication/authentication.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {
  email: string = '';
  password: string = '';
  isPasswordVisible = false;
  isLoading = false;
  showNotification = false;
  notificationMessage = '';
  notificationType: 'success' | 'error' = 'success';

  constructor(
    private router: Router, 
    private authService: AuthenticationService
  ) {}

  ngOnInit() {
    AOS.init();
  }

  togglePasswordVisibility() {
    this.isPasswordVisible = !this.isPasswordVisible;
  }

  showNotificationMessage(message: string, type: 'success' | 'error') {
    this.notificationMessage = message;
    this.notificationType = type;
    this.showNotification = true;
    setTimeout(() => {
      this.showNotification = false;
    }, 5000);
  }

  login() {
    if (!this.email || !this.password) {
      this.showNotificationMessage('Please enter both email and password', 'error');
      return;
    }

    this.isLoading = true;
    
    this.authService.login(this.email, this.password).subscribe({
      next: (response: any) => {
        this.isLoading = false;
        this.showNotificationMessage('Login successful! Redirecting...', 'success');
        setTimeout(() => {
          this.router.navigate(['']);
        }, 1500);
      },
      error: (error: any) => {
        this.isLoading = false;
        this.showNotificationMessage('Login failed. Please check your credentials.', 'error');
        console.error('Login error', error);
      }
    });
  }

  navigateToRegister() {
    this.router.navigate(['register']);
  }

  navigateToForgotPass() {
    this.router.navigate(['forgot-pass']);
  }
}