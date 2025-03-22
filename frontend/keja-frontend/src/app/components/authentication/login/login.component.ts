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

  constructor(private router: Router, private authService: AuthenticationService) {}

  ngOnInit() {
    AOS.init();
  }

  togglePasswordVisibility() {
    this.isPasswordVisible = !this.isPasswordVisible;
  }

  login() {
    if (this.email && this.password) {
      this.authService.login(this.email, this.password).subscribe(
        (response: any) => {
          console.log('Login successful', response);
          this.router.navigate(['/dashboard']); // Navigate to a dashboard or homepage on successful login
        },
        (error: any) => {
          console.error('Login error', error);
        }
      );
    }
  }

  navigateToRegister() {
    this.router.navigate(['register']);
  }

  navigateToForgotPass() {
    this.router.navigate(['forgot-pass']);
  }
}
