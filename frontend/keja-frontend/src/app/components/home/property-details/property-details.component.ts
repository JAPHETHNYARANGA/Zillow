import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';

@Component({
  selector: 'app-property-details',
  templateUrl: './property-details.component.html',
  styleUrls: ['./property-details.component.scss']
})
export class PropertyDetailsComponent implements OnInit {
  propertyId: string | null = null;
  property: any;

  // Sample property data (for demonstration purposes)
  properties = [
    {
      id: '1',
      image: 'assets/property.svg',
      price: 'Kesh 2,259,000',
      details: '3 bed | 2.5 bath | 1,537 sqft',
      description: 'Beautiful modern house located in a quiet neighborhood with a garden and pool.',
      additionalImages: [
        'assets/property.svg',
        'assets/property.svg',
        'assets/property.svg'
      ],
      area: '1,537',
      bedrooms: '3',
      bathrooms: '2.5',
      yearBuilt: '2021'
    },
    // Add more properties here
  ];

  constructor(private route: ActivatedRoute) {}

  ngOnInit(): void {
    this.propertyId = this.route.snapshot.paramMap.get('id');
    if (this.propertyId) {
      this.property = this.properties.find(p => p.id === this.propertyId);
    }
  }
}
