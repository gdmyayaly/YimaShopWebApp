import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { ListMediaComponent } from './list-media/list-media.component';
import { AddMediaComponent } from './add-media/add-media.component';
import { MediaRoutingModule } from './media-routing.module';
import { HTTP_INTERCEPTORS, HttpClientModule } from '@angular/common/http';
import { MediaComponent } from './media.component';


@NgModule({
  declarations: [
    MediaComponent,
    ListMediaComponent,
    AddMediaComponent
  ],
  imports: [
    CommonModule,
    HttpClientModule,
    MediaRoutingModule,
    
  ]
})
export class MediaModule { }
