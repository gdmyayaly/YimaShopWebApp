export interface MediaModel {
    id?: number; // Facultatif avant l'envoi
    name: string;
    url?: string; // Facultatif avant l'envoi
    type: string;
    size: number;
    createdAt?: string; // Facultatif avant l'envoi
    progress?: number; // Ajouté pour le suivi de la progression
    status?: 'pending' | 'uploading' | 'success' | 'error'; // Suivi de l'état
    file?: File; // Nouveau champ pour le fichier brut
  }
  