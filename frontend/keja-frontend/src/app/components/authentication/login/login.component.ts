import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import AOS from 'aos';


@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit{

  constructor(private router:Router){}

  ngOnInit(){
    AOS.init();
  }

  isPasswordVisible = false; 

  togglePasswordVisibility() {
    this.isPasswordVisible = !this.isPasswordVisible;
  }

  navigateToRegister(){
    this.router.navigate(['register']);
  }

  navigateToForgotPass(){
    this.router.navigate(['forgot-pass']);
  }

}
