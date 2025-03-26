import { Component, OnInit } from '@angular/core';
import { PropertyService } from 'src/app/services/properties/property.service';

@Component({
  selector: 'app-homepage',
  templateUrl: './homepage.component.html',
  styleUrls: ['./homepage.component.scss']
})
export class HomepageComponent implements OnInit{


  constructor(private propertyService: PropertyService){}

  properties: any[] = [];
  pagination: any = {};
  isLoading = true;

  isModalOpen = false;  // To toggle modal visibility
  isLoggedIn = false;
  previewImages: { file: File, url: string }[] = [];

  ngOnInit(): void {
    this.checkLoginStatus(); 
    this.loadProperties();
  }

  newProperty: any = {
    title: '',
    description: '',
    type: '',
    listing_type: '',
    price: 0,
    address: '',
    city: '',
    state: '',
    zip_code: '',
    bedrooms: null,
    bathrooms: null,
    square_feet: null,
    furnished: '',
    lease_term_months: null,
    images: []
  };

  checkLoginStatus() {
    // Example logic to check login status
    const user = localStorage.getItem('auth_token');  // Assuming login info is saved in localStorage
    this.isLoggedIn = user !== null;  // If there is user data, the user is logged in
  }
  // Open the modal to create a new property
  openModal() {
    this.isModalOpen = true;
  }

  // Close the modal
  closeModal() {
    this.isModalOpen = false;
  }

  resetForm() {
    this.newProperty = {
      title: '',
      description: '',
      type: '',
      listing_type: '',
      price: 0,
      address: '',
      city: '',
      state: '',
      zip_code: '',
      bedrooms: null,
      bathrooms: null,
      square_feet: null,
      furnished: '',
      lease_term_months: null,
      images: []
    };
    this.previewImages = [];
  }

  onFileSelected(event: any) {
    const files = event.target.files;
    if (files) {
      for (let i = 0; i < files.length; i++) {
        const file = files[i];
        const reader = new FileReader();
        reader.onload = (e: any) => {
          this.previewImages.push({
            file: file,
            url: e.target.result
          });
        };
        reader.readAsDataURL(file);
      }
    }
  }

  removeImage(image: any) {
    this.previewImages = this.previewImages.filter(img => img.url !== image.url);
  }
  // Create property logic
  createProperty() {
    
    // Prepare FormData for file upload
    const formData = new FormData();
    
    // Add property data
    Object.keys(this.newProperty).forEach(key => {
      if (key !== 'images' && this.newProperty[key] !== null) {
        formData.append(key, this.newProperty[key]);
      }
    });
    
    // Add images
    this.previewImages.forEach(image => {
      formData.append('images[]', image.file);
    });
    
    this.propertyService.createProperty(formData).subscribe(
      response => {
        console.log('Property created successfully', response);
        this.closeModal();
        this.loadProperties();
      },
      error => {
        console.error('Error creating property', error);
      }
    );
    
    console.log('Form data prepared:', formData);
    this.closeModal();
  }


  loadProperties(page: number = 1): void {
    this.isLoading = true;
    this.propertyService.getProperties(page).subscribe({
      next: (response) => {
        this.properties = response.data;
        this.pagination = {
          currentPage: response.current_page,
          lastPage: response.last_page,
          total: response.total
        };
        this.isLoading = false;
      },
      error: (err) => {
        console.error('Error loading properties:', err);
        this.isLoading = false;
      }
    });
  }

  onPageChange(page: number): void {
    this.loadProperties(page);
  }

  // Helper function to format price
  formatPrice(price: string): string {
    return `KSh ${parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2})}`;
  }

  // Helper function to get first image URL
  getImageUrl(imagePath: string): string {
    return imagePath 
      ? `http://127.0.0.1:8000/storage/properties/${imagePath}`
      : 'assets/property.svg';
  }  
}
