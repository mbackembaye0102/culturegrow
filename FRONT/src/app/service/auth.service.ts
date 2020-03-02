import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { JwtHelperService } from "@auth0/angular-jwt";
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  private urllogin:string="http://127.0.0.1:8000/login";
  private urlInfos:string="http://127.0.0.1:8000/infos";
   jwt: string;
  constructor(private http: HttpClient,private route:Router) { }
  logger(data){
    return this.http.post(this.urllogin , data , {observe:'response'})
  }
  infosconnecter(){
   // console.log(data);
    return this.http.post(this.urlInfos  , {observe:'response'})
  }
  enregistrementToken(jwtToken : string){ 
    localStorage.setItem('token',jwtToken);
    this.jwt=jwtToken;
    this.route.navigate(["/test"]);
  //  this.recuperation();
  }
  recuperation(){
    let jwtHelper = new JwtHelperService();
    let objet= jwtHelper.decodeToken(this.jwt);
    console.log(objet.aut);
    this.route.navigate(["/test"]);
    //  this.role=objet.roles;
    //  localStorage.setItem('role',objet.roles[0]);
    //  if (this.role[0]=="ROLE_ALL") {
    //    this.router.navigate(["/listusersysteme"]);
    //  }
    //  if (this.role[0]=="ROLE_ADMIN") {
    //    this.router.navigate(["/listusersysteme"]);
    //  }
    //  if (this.role[0]=="ROLE_CAISSIER") {
    //    this.router.navigate(["/depot"]);
    //  }
    //  if (this.role[0]=="ROLE_PRESTATAIRE") {
    //    this.router.navigate(["/listeuser"]);
    //  }
    //  if (this.role[0]=="ROLE_UTILISATEUR") {
    //    this.router.navigate(["/transfert"]);
    //  }
    //  if (this.role[0]=="ROLE_ADMINISTRATEUR") {
    //    this.router.navigate(["/listeuser"]);
    //  }
}
  getToken(){
    return this.jwt=localStorage.getItem('token');
  }
}
