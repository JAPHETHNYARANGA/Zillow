import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { PropertyService } from 'src/app/services/properties/property.service';
import AOS from 'aos';

@Component({
  selector: 'app-property-details',
  templateUrl: './property-details.component.html',
  styleUrls: ['./property-details.component.scss']
})
export class PropertyDetailsComponent implements OnInit {
  propertyId: string | null = null;
  property: any;
  isLoading = true;
  selectedImageIndex = 0;
  showContactModal = false;
  contactMessage = '';
  isSendingMessage = false;
  contactSuccess = false;

  constructor(
    private route: ActivatedRoute,
    private propertyService: PropertyService
  ) {}

  ngOnInit(): void {
    AOS.init({
      duration: 800,
      easing: 'ease-in-out',
      once: true
    });
    
    this.propertyId = this.route.snapshot.paramMap.get('id');
    if (this.propertyId) {
      this.loadProperty(this.propertyId);
    }
  }

  loadProperty(id: string): void {
    this.isLoading = true;
    this.propertyService.getPropertyById(+id).subscribe({
      next: (response) => {
        this.property = response;
        this.isLoading = false;
      },
      error: (err) => {
        console.error('Error loading property:', err);
        this.isLoading = false;
      }
    });
  }

  getImageUrl(imagePath: string): string {
    return imagePath || 'assets/property.svg';
  }

  formatPrice(price: string): string {
    return `KSh ${parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2})}`;
  }

  selectImage(index: number): void {
    this.selectedImageIndex = index;
  }

  openContactModal(): void {
    this.showContactModal = true;
    this.contactSuccess = false;
    this.contactMessage = '';
  }

  closeContactModal(): void {
    this.showContactModal = false;
  }

  sendContactMessage(): void {
    if (!this.contactMessage.trim()) return;
    
    this.isSendingMessage = true;
    
    // In a real app, you would call your backend service here
    // For now, we'll simulate the API call
    setTimeout(() => {
      this.isSendingMessage = false;
      this.contactSuccess = true;
      
      // Hide success message after 3 seconds and close modal
      setTimeout(() => {
        this.showContactModal = false;
      }, 3000);
    }, 1500);
  }
}