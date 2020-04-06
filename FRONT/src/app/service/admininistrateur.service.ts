import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class AdmininistrateurService {
  private url="http://127.0.0.1:8000/admin/";
  // private urlsaveuser:string="saveusergrow";
  private urllistuser:string="listuser";
  // private urllisteteamgrow:string="growteam";
  // private urllistepostegrow:string="growposte";
  // private urladdstructure:string="savestructure";
  // private urllistestructure:string="listestructure";
  private urladdteamstructure:string="addteamstructure";
  // private urloneteamstructure:string="oneteamstructure";
  // private urlsaveoneteamstructure:string="saveoneteamstructure";
  // private urluserteam:string="userteam";
  // private urlsaveuserteam:string="saveuserteam";
  // private urldetailuser:string="detailuser";
  // private urlallstructure:string="allstructure";
  // private urlsavesession="savesession";
  public idteam=0;
  constructor(private http: HttpClient) { }
//  saveuser(data,fileToUpload){
//   const formData: FormData= new FormData();
//   formData.append('prenom', data.prenom);
//   formData.append('nom', data.nom);
//   formData.append('email', data.email);
//   formData.append('poste', data.poste);
//   formData.append('profil', data.profil);
//   formData.append('telephone', data.telephone);
//   formData.append('taille', data.taille);
//   formData.append('team1', data.team1);
//   formData.append('team2', data.team2);
//   formData.append('image', fileToUpload);
//   return this.http.post(this.url+this.urlsaveuser,formData,{observe:'response'})
//   }
  listuser(){
    return this.http.post(this.url+this.urllistuser , {observe:'response'})
  }
  // listeteamgrow(){
  //   return this.http.post(this.url+this.urllisteteamgrow,{observe:'response'})
  // }
  // listepostegrow(){
  //   return this.http.post(this.url+this.urllistepostegrow,{observe:'response'})
  // }
  // addstructure(data,fileToUpload){
  //   const formData: FormData= new FormData();
  //   formData.append('nom', data.nom);
  //   formData.append('image', fileToUpload);
  //   return this.http.post(this.url+this.urladdstructure,formData,{observe:'response'})
  // }
  // listestructure(){
  //   return this.http.post(this.url+this.urllistestructure,{observe:'response'})
  // }
  addteamstructure(data){
    return this.http.post(this.url+this.urladdteamstructure,data,{observe:'response'})
  }
  // oneteamstructure(data){
  //   return this.http.post(this.url+this.urloneteamstructure,data,{observe:'response'})
  // }
  // saveoneteamstructure(data,fileToUpload){
  //   const formData: FormData= new FormData();
  //   formData.append('nom', data.nom);
  //   formData.append('id', data.id);
  //   formData.append('image', fileToUpload);
  //   return this.http.post(this.url+this.urlsaveoneteamstructure,formData,{observe:'response'})
  // }
  // userteam(data){
  //   return this.http.post(this.url+this.urluserteam,data,{observe:'response'})
  // }
  // saveuserteam(data,fileToUpload){
  //   const formData: FormData= new FormData();
  //   formData.append('username', data.username);
  //   formData.append('prenom', data.prenom);
  //   formData.append('nom', data.nom);
  //   formData.append('telephone', data.telephone);
  //   formData.append('profil', data.profil);
  //   formData.append('image', fileToUpload, fileToUpload.name);
  //   return this.http.post(this.url+this.urlsaveuserteam,formData,{observe:'response'})
  // }
  // detailuser(data){
  //   return this.http.post(this.url+this.urldetailuser,data,{observe:'response'})
  // }
  // allstructure(){
  //   console.log(this.url+this.urlallstructure)
  //   return this.http.post(this.url+this.urlallstructure,{observe:'response'})
  // }
  // savesession(data){
  //   return this.http.post(this.url+this.urlsavesession,data,{observe:'response'})
  // }
}
