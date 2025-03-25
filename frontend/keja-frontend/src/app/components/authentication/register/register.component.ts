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

  constructor(private fb: FormBuilder, private router: Router, private authService: AuthenticationService) {}

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

  onSubmit(): void {
    if (this.registerForm.valid) {
      this.authService.register(this.registerForm.value).subscribe(
        (response: any) => {
          console.log('Registration successful', response);
          this.router.navigate(['']);
        },
        (error: any) => {
          console.error('Registration error', error);
        }
      );
    }
  }

  navigateToLogin() {
    this.router.navigate(['login']);
  }
}
