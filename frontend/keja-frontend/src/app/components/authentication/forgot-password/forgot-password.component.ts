import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthenticationService } from 'src/app/services/authentication/authentication.service';

@Component({
  selector: 'app-forgot-password',
  templateUrl: './forgot-password.component.html',
  styleUrls: ['./forgot-password.component.scss']
})
export class ForgotPasswordComponent {
  email: string = '';
  isLoading = false;
  showNotification = false;
  notificationMessage = '';
  notificationType: 'success' | 'error' = 'success';

  constructor(
    private authService: AuthenticationService, 
    private router: Router
  ) {}

  showNotificationMessage(message: string, type: 'success' | 'error') {
    this.notificationMessage = message;
    this.notificationType = type;
    this.showNotification = true;
    setTimeout(() => {
      this.showNotification = false;
    }, 5000);
  }

  submit() {
    if (!this.email) {
      this.showNotificationMessage('Please enter your email address', 'error');
      return;
    }

    this.isLoading = true;
    
    this.authService.forgotPassword(this.email).subscribe({
      next: (response) => {
        this.isLoading = false;
        this.showNotificationMessage('Password reset link sent to your email!', 'success');
        setTimeout(() => {
          this.router.navigate(['/login']);
        }, 2000);
      },
      error: (error) => {
        this.isLoading = false;
        const errorMessage = error.error?.message || 'Error sending password reset request';
        this.showNotificationMessage(errorMessage, 'error');
        console.error('Error sending password reset request', error);
      }
    });
  }

  navigateToLogin() {
    this.router.navigate(['login']);
  }
}