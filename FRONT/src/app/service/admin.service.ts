import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class AdminService {
  private urlsaveuser:string="http://127.0.0.1:8000/admin/saveusergrow";
  private urllistuser:string="http://127.0.0.1:8000/admin/listuser";
  private urllisteteamgrow:string="http://127.0.0.1:8000/admin/growteam";
  private urllistepostegrow:string="http://127.0.0.1:8000/admin/growposte";
  private urladdstructure:string="http://127.0.0.1:8000/admin/savestructure";
  private urllistestructure:string="http://127.0.0.1:8000/admin/listestructure";
  private urladdteamstructure:string="http://127.0.0.1:8000/admin/addteamstructure";
  private urloneteamstructure:string="http://127.0.0.1:8000/admin/oneteamstructure";
  private urlsaveoneteamstructure:string="http://127.0.0.1:8000/admin/saveoneteamstructure";
  private urluserteam:string="http://127.0.0.1:8000/admin/userteam";
  private urlsaveuserteam:string="http://127.0.0.1:8000/admin/saveuserteam";
  private urldetailuser:string="http://127.0.0.1:8000/admin/detailuser";
  
  public idteam=0;
  constructor(private http: HttpClient) { }
 saveuser(data,fileToUpload){
  const formData: FormData= new FormData();
  formData.append('prenom', data.prenom);
  formData.append('nom', data.nom);
  formData.append('email', data.email);
  formData.append('poste', data.poste);
  formData.append('profil', data.profil);
  formData.append('telephone', data.telephone);
  formData.append('taille', data.taille);
  formData.append('team1', data.team1);
  formData.append('team2', data.team2);
  formData.append('image', fileToUpload);
  return this.http.post(this.urlsaveuser,formData,{observe:'response'})
   // return this.http.post(this.urlsaveuser , data , {observe:'response'})
  }
  listuser(){
    return this.http.post(this.urllistuser , {observe:'response'})
  }
  listeteamgrow(){
    return this.http.post(this.urllisteteamgrow,{observe:'response'})
  }
  listepostegrow(){
    return this.http.post(this.urllistepostegrow,{observe:'response'})
  }
  addstructure(data){
    return this.http.post(this.urladdstructure,data,{observe:'response'})
  }
  listestructure(){
    return this.http.post(this.urllistestructure,{observe:'response'})
  }
  addteamstructure(data){
    return this.http.post(this.urladdteamstructure,data,{observe:'response'})
  }
  oneteamstructure(data){
    return this.http.post(this.urloneteamstructure,data,{observe:'response'})
  }
  saveoneteamstructure(data){
    return this.http.post(this.urlsaveoneteamstructure,data,{observe:'response'})
  }
  userteam(data){
    return this.http.post(this.urluserteam,data,{observe:'response'})
  }
  saveuserteam(data,fileToUpload){
    const formData: FormData= new FormData();
    formData.append('username', data.username);
    formData.append('prenom', data.prenom);
    formData.append('nom', data.nom);
    formData.append('telephone', data.telephone);
    formData.append('profil', data.profil);
    formData.append('image', fileToUpload, fileToUpload.name);
    return this.http.post(this.urlsaveuserteam,formData,{observe:'response'})
  }
  detailuser(data){
    return this.http.post(this.urldetailuser,data,{observe:'response'})
  }
}
