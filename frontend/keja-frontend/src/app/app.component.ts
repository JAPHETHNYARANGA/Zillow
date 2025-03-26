import { Component } from '@angular/core';
import { NavigationEnd, Router } from '@angular/router';
import { filter } from 'rxjs';

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {
  title = 'keja-frontend';
  showHeaderAndFooter = true;

  constructor(private router: Router) {
    // Subscribe to route changes
    this.router.events
      .pipe(filter(event => event instanceof NavigationEnd))
      .subscribe(event => {
        // Define routes that shouldn't show the header and footer
        const noHeaderFooterRoutes = ['login', 'register', 'forgot-password'];
        const currentRoute = this.router.url.split('/')[1];

        // Check if the current route should not show header and footer
        this.showHeaderAndFooter = !noHeaderFooterRoutes.includes(currentRoute);
      });
  }
}
