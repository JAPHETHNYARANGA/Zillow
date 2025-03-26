import { Component, OnInit } from '@angular/core';
import AOS from 'aos';

import { Router } from '@angular/router';  // Import Router for navigation
import { AuthenticationService } from 'src/app/services/authentication/authentication.service';

@Component({
  selector: 'app-nav-bar',
  templateUrl: './nav-bar.component.html',
  styleUrls: ['./nav-bar.component.scss']
})
export class NavBarComponent implements OnInit {
  isMenuOpen: boolean = false;  // Define the isMenuOpen property
  isAuthenticated: boolean = false;  // To track if user is logged in

  constructor(
    private authService: AuthenticationService,  // Inject the AuthenticationService
    private router: Router  // Inject Router to navigate
  ) {}

  ngOnInit(): void {
    AOS.init();
    this.checkAuthentication();  // Check if user is authenticated on component load
  }

  toggleMenu() {
    this.isMenuOpen = !this.isMenuOpen;
  }

  // Check if a token exists in localStorage to set the authentication status
  checkAuthentication(): void {
    const token = this.authService.getAuthToken();  // Get the token from localStorage
    this.isAuthenticated = !!token;  // If token exists, user is authenticated
  }

  navigateToContact(){
    this.router.navigate(['contact']);
  }
  navigateToAbout(){
    this.router.navigate(['about']);
  }

  // Logout method
  logout(): void {
    this.authService.logout();  // Remove token from localStorage
    this.isAuthenticated = false;  // Update authentication status
    this.router.navigate(['']);  // Redirect to login page
  }

  // Login method (redirect to the login page)
  login(): void {
    this.router.navigate(['/login']);  // Redirect to login page
  }
}
