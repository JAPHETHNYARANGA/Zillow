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

  constructor(private authService: AuthenticationService, private router: Router) {}

  submit() {
    if (this.email) {
      this.authService.forgotPassword(this.email).subscribe(
        (response) => {
          console.log('Password reset request sent', response);
          this.router.navigate(['/login']);
        },
        (error) => {
          console.error('Error sending password reset request', error);
        }
      );
    }
  }

  navigateToLogin(){
    this.router.navigate([''])
  }
}
