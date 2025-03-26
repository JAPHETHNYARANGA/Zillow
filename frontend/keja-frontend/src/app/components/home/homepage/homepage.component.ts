import { Component, OnInit } from '@angular/core';
import { PropertyService } from 'src/app/services/properties/property.service';

@Component({
  selector: 'app-homepage',
  templateUrl: './homepage.component.html',
  styleUrls: ['./homepage.component.scss']
})
export class HomepageComponent implements OnInit {
  properties: any[] = [];
  pagination: any = {};
  isLoading = true;
  isModalOpen = false;
  isLoggedIn = false;
  previewImages: { file: File, url: string }[] = [];
  isSubmitting = false;
  showNotification = false;
  notificationMessage = '';
  notificationType: 'success' | 'error' = 'success';

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

  constructor(private propertyService: PropertyService) {}

  ngOnInit(): void {
    this.checkLoginStatus(); 
    this.loadProperties();
  }

  getImageUrl(images: string[] | undefined): string {
    if (images && images.length > 0) {
      return images[0];
    }
    return 'assets/property.svg';
  }

  handleImageError(event: any) {
    event.target.src = 'assets/property.svg';
  }

  checkLoginStatus() {
    const user = localStorage.getItem('auth_token');
    this.isLoggedIn = user !== null;
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

  openModal() {
    this.isModalOpen = true;
  }

  closeModal() {
    this.isModalOpen = false;
    this.resetForm();
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

  showNotificationMessage(message: string, type: 'success' | 'error') {
    this.notificationMessage = message;
    this.notificationType = type;
    this.showNotification = true;
    setTimeout(() => {
      this.showNotification = false;
    }, 5000);
  }

  createProperty() {
    // Form validation
    if (!this.newProperty.title || !this.newProperty.description || 
        !this.newProperty.type || !this.newProperty.listing_type ||
        !this.newProperty.price || !this.newProperty.address ||
        !this.newProperty.city || !this.newProperty.state ||
        !this.newProperty.zip_code || !this.newProperty.furnished) {
      this.showNotificationMessage('Please fill in all required fields', 'error');
      return;
    }

    if (this.previewImages.length === 0) {
      this.showNotificationMessage('Please upload at least one image', 'error');
      return;
    }

    this.isSubmitting = true;

    const formData = new FormData();
    
    Object.keys(this.newProperty).forEach(key => {
      if (key !== 'images' && this.newProperty[key] !== null) {
        formData.append(key, this.newProperty[key]);
      }
    });
    
    this.previewImages.forEach(image => {
      formData.append('images[]', image.file);
    });
    
    this.propertyService.createProperty(formData).subscribe({
      next: (response) => {
        this.isSubmitting = false;
        this.showNotificationMessage('Property created successfully!', 'success');
        this.closeModal();
        this.loadProperties();
      },
      error: (error) => {
        this.isSubmitting = false;
        this.showNotificationMessage('Error creating property. Please try again.', 'error');
        console.error('Error creating property', error);
      }
    });
  }

  formatPrice(price: string): string {
    return `KSh ${parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2})}`;
  }

  onPageChange(page: number): void {
    this.loadProperties(page);
  }
}