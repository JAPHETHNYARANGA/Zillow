import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './components/authentication/login/login.component';
import { RegisterComponent } from './components/authentication/register/register.component';
import { ForgotPasswordComponent } from './components/authentication/forgot-password/forgot-password.component';
import { HomepageComponent } from './components/home/homepage/homepage.component';

const routes: Routes = [
  {path:'', component:LoginComponent},
  {path:'register', component:RegisterComponent},
  {path:'forgot-pass', component:ForgotPasswordComponent},
  {path:'home', component:HomepageComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
