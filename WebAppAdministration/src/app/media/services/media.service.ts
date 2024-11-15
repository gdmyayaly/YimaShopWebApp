import { Injectable } from '@angular/core';
import { HttpClient, HttpEventType } from '@angular/common/http';
import { Observable,Subject  } from 'rxjs';
import { MediaModel } from '../model/media.model';
@Injectable({
  providedIn: 'root'
})
export class MediaService {

  private apiUrl = 'http://127.0.0.1:8000/apis/media'; // URL de l'API Symfony

  constructor(private http: HttpClient) {}

  getAllMedia(): Observable<any[]> {
    return this.http.get<any[]>(this.apiUrl);
  }

  deleteMedia(id: number): Observable<void> {
    return this.http.delete<void>(`${this.apiUrl}/${id}`);
  }
  uploadMedia(media: MediaModel): Observable<number> {
    const formData = new FormData();
    if (media.file) {
      formData.append('files', media.file, media.name); // Ajoute le fichier réel
    }
    const progressSubject = new Subject<number>();

    this.http.post(this.apiUrl, formData, {
      reportProgress: true,
      observe: 'events'
    }).subscribe({
      next: (event) => {
        if (event.type === HttpEventType.UploadProgress) {
          const progress = Math.round((event.loaded / (event.total || 1)) * 100);
          progressSubject.next(progress);
        } else if (event.type === HttpEventType.Response) {
          progressSubject.complete();
        }
      },
      error: () => {
        progressSubject.error('Upload failed');
      }
    });
    return progressSubject.asObservable();
  }
}
