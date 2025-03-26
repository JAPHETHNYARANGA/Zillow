import { Component } from '@angular/core';

@Component({
  selector: 'app-homepage',
  templateUrl: './homepage.component.html',
  styleUrls: ['./homepage.component.scss']
})
export class HomepageComponent {
  properties = [
    { id: 1, image: 'assets/property.svg', price: 'Kesh 2,259,000', details: '3 bed | 2.5 bath | 1,537 sqft' },
    { id: 2, image: 'assets/property.svg', price: 'Kesh 2,259,000', details: '2 bed | 1 bath | 900 sqft' },
    { id: 3, image: 'assets/property.svg', price: 'Kesh 11,600,000', details: 'New Construction' },
  ];

  isModalOpen = false;  // To toggle modal visibility
  newProperty = { price: '', details: '' };  // To store new property data

  // Open the modal to create a new property
  openModal() {
    this.isModalOpen = true;
  }

  // Close the modal
  closeModal() {
    this.isModalOpen = false;
  }

  // Create property logic
  createProperty() {
    // For now, we'll just log the property data
    console.log('New Property:', this.newProperty);
    
    // Add the new property to the properties array (you can replace this with an API call)
    const newId = this.properties.length + 1;  // Simple id increment
    this.properties.push({
      id: newId, ...this.newProperty,
      image: ''
    });

    // Reset the form and close the modal
    this.newProperty = { price: '', details: '' };
    this.closeModal();
  }
}
