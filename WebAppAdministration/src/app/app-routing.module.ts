import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { LoginComponent } from './login/login.component';

const routes: Routes = [
  {path:'login',component:LoginComponent},
  {
    path:'media',
    loadChildren: () => import('./media/media.module').then( m => m.MediaModule)
  },
  { path: '',   redirectTo: '/login', pathMatch: 'full' }, // redirect to `login`
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
