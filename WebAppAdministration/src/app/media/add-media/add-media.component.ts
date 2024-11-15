import { Component } from '@angular/core';
import { MediaModel } from '../model/media.model';
import { MediaService } from '../services/media.service';
import { ToastrService } from 'ngx-toastr';

@Component({
  selector: 'app-add-media',
  templateUrl: './add-media.component.html',
  styleUrls: ['./add-media.component.css']
})
export class AddMediaComponent {
  mediaList: MediaModel[] = [];

  constructor(private mediaService: MediaService,private toastr: ToastrService) {}

  onFileSelected(event: Event) {
    const files = (event.target as HTMLInputElement).files;

    if (files) {
      for (let i = 0; i < files.length; i++) {
        const file = files[i];

        const reader = new FileReader();
        reader.onload = (e: any) => {
          this.mediaList.push({
            name: file.name,
            url: e.target.result,
            type: file.type,
            size: file.size,
            progress: 0,
            status: 'pending',
            file:file
          });
        };
        reader.readAsDataURL(file);
      }
    }
  }

  removeMedia(index: number) {
    this.mediaList.splice(index, 1);
  }

  uploadAll() {
    this.mediaList.forEach((media, index) => {
      if (media.status === 'pending' || media.status === 'error') {
        this.uploadMedia(media, index);
      }
    });
  }

  // uploadMedia(media: MediaModel, index: number) {
  //   media.status = 'uploading';

  //   this.mediaService.uploadMedia(media).subscribe({
  //     next: (progress: number) => {
  //       media.progress = progress;
  //     },
  //     complete: () => {
  //       media.status = 'success';
  //     },
  //     error: () => {
  //       media.status = 'error';
  //     }
  //   });
  // }
  uploadMedia(media: MediaModel, index: number) {
    if (!media.file) {
      this.toastr.error(`Le fichier pour ${media.name} est manquant.`);
      return;
    }
    media.status = 'uploading';
  
    this.mediaService.uploadMedia(media).subscribe({
      next: (progress: number) => {
        media.progress = progress;
      },
      complete: () => {
        media.status = 'success';
        this.toastr.success(`Le fichier ${media.name} a été téléchargé avec succès.`);
      },
      error: () => {
        media.status = 'error';
        this.toastr.error(`Échec du téléchargement pour ${media.name}.`);
      }
    });
  }
}
