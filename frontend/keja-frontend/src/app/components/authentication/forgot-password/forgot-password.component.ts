import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import AOS from 'aos';

@Component({
  selector: 'app-forgot-password',
  templateUrl: './forgot-password.component.html',
  styleUrls: ['./forgot-password.component.scss']
})
export class ForgotPasswordComponent implements OnInit{
  constructor(private router:Router){}

  ngOnInit(): void {
      AOS.init();
  }
  navigateToLogin(){
    this.router.navigate(['']);
  }
}
