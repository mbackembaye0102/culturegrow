import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
    providedIn: 'root'
})
export class AdminService {
    private url = "http://127.0.0.1:8000/admin/";
    private urllistuser = "usergrow";
    private urllisteteamgrow: string = "growteam";
    private urllistepostegrow: string = "growposte";
    private urlsaveuser: string = "saveusergrow";
    private urldetailuser: string = "detailuser";
    private urlallstructure: string = "allstructure";
    private urloneteamstructure: string = "oneteamstructure";
    private urlsavesession = "savesession";
    private urluserteam: string = "userteam";
    private urlsaveuserteam: string = "saveuserteam";
    private urladdgrowteam: string = "addgrowteam";
    private urllistestructure:string="listestructure";
    private urladdstructure:string="addstructure";
    private urlsaveoneteamstructure:string="saveoneteamstructure";
    private urllistementor:string="listementor";
    private urluserteamevaluation="userteamevaluation";
    private urlsaveevaluation="saveevaluation";
    private urlusersession="usersession";
    private urlusersessionteam="usersessionteam";
    private urluserdetailsessionevaluation="userdetailsessionevaluation";
    
    public usersessiondata={iduser:null,idsession:null,team:null}

    constructor(private http: HttpClient) { }
    usergrow() {
        return this.http.post(this.url + this.urllistuser, { observe: 'response' })
    }
    listeteamgrow() {
        return this.http.post(this.url + this.urllisteteamgrow, { observe: 'response' })
    }
    listepostegrow() {
        return this.http.post(this.url + this.urllistepostegrow, { observe: 'response' })
    }
    saveuser(data, fileToUpload) {
        const formData: FormData = new FormData();
        formData.append('prenom', data.prenom);
        formData.append('nom', data.nom);
        formData.append('email', data.email);
        formData.append('poste', data.poste);
        for (let index = 0; index <= data.taille; index++) {
            let r = 'team' + index;
            formData.append(r, data.team[index]);
        }
        formData.append('profil', data.profil);
        formData.append('telephone', data.telephone);
        formData.append('taille', data.taille);
        formData.append('image', fileToUpload);
        return this.http.post(this.url + this.urlsaveuser, formData, { observe: 'response' })
    }
    detailuser(data) {
        return this.http.post(this.url + this.urldetailuser, data, { observe: 'response' })
    }
    allstructure() {
        console.log(this.url + this.urlallstructure)
        return this.http.post(this.url + this.urlallstructure, { observe: 'response' })
    }
    oneteamstructure(data) {
        return this.http.post(this.url + this.urloneteamstructure, data, { observe: 'response' })
    }
    savesession(data) {
        return this.http.post(this.url + this.urlsavesession, data, { observe: 'response' })
    }
    userteam(data) {
        return this.http.post(this.url + this.urluserteam, data, { observe: 'response' })
    }
    saveuserteam(data, fileToUpload) {
        const formData: FormData = new FormData();
        formData.append('email', data.email);
        formData.append('mentor', data.mentor);
        formData.append('id', data.id);
        formData.append('prenom', data.prenom);
        formData.append('nom', data.nom);
        formData.append('poste', data.poste);
        formData.append('telephone', data.telephone);
        formData.append('nomtuteur', data.nomtuteur);
        formData.append('telephonetuteur', data.telephonetuteur);
        formData.append('image', fileToUpload);
        return this.http.post(this.url + this.urlsaveuserteam, formData, { observe: 'response' })
    }
    addgrowteam(data, fileToUpload){
        const formData: FormData = new FormData();
        formData.append('nom', data.nom);
        formData.append('image', fileToUpload);
        return this.http.post(this.url + this.urladdgrowteam, formData, { observe: 'response' })
    }
    listestructure(){
        return this.http.post(this.url+this.urllistestructure,{observe:'response'})
      }
      addstructure(data,fileToUpload){
        const formData: FormData= new FormData();
        formData.append('nom', data.nom);
        formData.append('image', fileToUpload);
        return this.http.post(this.url+this.urladdstructure,formData,{observe:'response'})
      }
      saveoneteamstructure(data,fileToUpload){
        const formData: FormData= new FormData();
        formData.append('nom', data.nom);
        formData.append('id', data.id);
        formData.append('image', fileToUpload);
        return this.http.post(this.url+this.urlsaveoneteamstructure,formData,{observe:'response'})
      }
      listementor(){
        return this.http.post(this.url+this.urllistementor,{observe:'response'})
      }
      userteamevaluation(donner){
        return this.http.post(this.url+this.urluserteamevaluation,donner,{observe:'response'})
      }
      saveevaluation(donner){
        return this.http.post("http://127.0.0.1:8000/infos/"+this.urlsaveevaluation,donner,{observe:'response'})
      }
      usersession(donner){
        return this.http.post(this.url+this.urlusersession,donner,{observe:'response'})
      }
      usersessionteam(donner){
        return this.http.post(this.url+this.urlusersessionteam,donner,{observe:'response'})
      }
      userdetailsessionevaluation(donner){
        return this.http.post(this.url+this.urluserdetailsessionevaluation,donner,{observe:'response'})
      }
}