import { NgModule } from '@angular/core';
import { RouterModule, Routes } from '@angular/router';
import { MediaComponent } from './media.component';
import { ListMediaComponent } from './list-media/list-media.component';
import { AddMediaComponent } from './add-media/add-media.component';

const routes: Routes = [
  {
    path: '',
    component: MediaComponent,children:[
      {path: '', component: ListMediaComponent},
      {path: 'add', component: AddMediaComponent},
    ]
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule]
})
export class MediaRoutingModule { }
