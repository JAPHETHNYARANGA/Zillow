import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/authentication/login/login.component';
import { RegisterComponent } from './components/authentication/register/register.component';
import { ForgotPasswordComponent } from './components/authentication/forgot-password/forgot-password.component';
import { HomepageComponent } from './components/home/homepage/homepage.component';
import { PropertyDetailsComponent } from './components/home/property-details/property-details.component';
import { ContactUsComponent } from './components/shared/contact-us/contact-us.component';
import { AboutUsComponent } from './components/shared/about-us/about-us.component';

const routes: Routes = [
  {path:'login', component:LoginComponent},
  {path:'register', component:RegisterComponent},
  {path:'forgot-pass', component:ForgotPasswordComponent},
  {path:'contact', component:ContactUsComponent},
  {path:'about', component:AboutUsComponent},
  {path:'', component:HomepageComponent},
  { path: 'property-details/:id', component: PropertyDetailsComponent },
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
