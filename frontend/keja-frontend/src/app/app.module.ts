import { NgModule } from '@angular/core';
import { BrowserModule } from '@angular/platform-browser';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { RegisterComponent } from './components/authentication/register/register.component';
import { ForgotPasswordComponent } from './components/authentication/forgot-password/forgot-password.component';
import { NavBarComponent } from './components/shared/nav-bar/nav-bar.component';
import { FooterComponent } from './components/shared/footer/footer.component';
import { CommonModule } from '@angular/common';
import { FormsModule, ReactiveFormsModule } from '@angular/forms';
import { LoginComponent } from './components/authentication/login/login.component';
import { HttpClientModule } from '@angular/common/http';
import { HomepageComponent } from './components/home/homepage/homepage.component';
import { PropertyDetailsComponent } from './components/home/property-details/property-details.component';
import { ContactUsComponent } from './components/shared/contact-us/contact-us.component';
import { AboutUsComponent } from './components/shared/about-us/about-us.component';


@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    RegisterComponent,
    ForgotPasswordComponent,
    NavBarComponent,
    FooterComponent,
    HomepageComponent,
    PropertyDetailsComponent,
    ContactUsComponent,
    AboutUsComponent
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    CommonModule,
    ReactiveFormsModule,
    FormsModule,
    HttpClientModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
