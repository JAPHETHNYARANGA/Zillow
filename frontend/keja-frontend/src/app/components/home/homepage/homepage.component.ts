import { Component } from '@angular/core';

@Component({
  selector: 'app-homepage',
  templateUrl: './homepage.component.html',
  styleUrls: ['./homepage.component.scss']
})
export class HomepageComponent {
  properties = [
    { image: 'assets/property.svg', price: 'kesh 2,259,000', details: '3 bed | 2.5 bath | 1,537 sqft' },
    { image: 'assets/property.svg', price: 'kesh 2,259,000', details: '2 bed | 1 bath | 900 sqft' },
    { image: 'assets/property.svg', price: 'kesh 11,600,000', details: 'New Construction' },
  ];
}
