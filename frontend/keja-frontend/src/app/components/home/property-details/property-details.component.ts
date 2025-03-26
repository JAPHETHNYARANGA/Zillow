import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { PropertyService } from 'src/app/services/properties/property.service';

@Component({
  selector: 'app-property-details',
  templateUrl: './property-details.component.html',
  styleUrls: ['./property-details.component.scss']
})
export class PropertyDetailsComponent implements OnInit {
  propertyId: string | null = null;
  property: any;
  isLoading = true;

  constructor(
    private route: ActivatedRoute,
    private propertyService: PropertyService
  ) {}

  ngOnInit(): void {
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
    return imagePath 
      ? `http://127.0.0.1:8000/storage/properties/${imagePath}`
      : 'assets/property.svg';
  }

  formatPrice(price: string): string {
    return `KSh ${parseFloat(price).toLocaleString('en-US', {minimumFractionDigits: 2})}`;
  }
}