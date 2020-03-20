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
  role:any;
  public connecter=false;
  public deconnecter=true;
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
    let jwtHelper = new JwtHelperService();
    let objet= jwtHelper.decodeToken(this.jwt);
 //   this.role=objet.roles;
    localStorage.setItem('role',objet.roles[0]);
    this.recuperation();
  }
  logout(){
    localStorage.removeItem('token')
    this.connecter=false;
this.route.navigate(["/login"])
  }
  recuperation(){

//    console.log(objet);
   // this.route.navigate(["/test"]);

    //  if (this.role[0]=="ROLE_ALL") {
    //    this.router.navigate(["/listusersysteme"]);
    //  }
    let rrr=localStorage.getItem('role')
     if (rrr=="ROLE_ADMIN") {
      this.connecter=true;
      this.deconnecter=false;
      this.route.navigate(["/collaborateur"]);
      // this.router.navigate(["/listusersysteme"]);
     }
     else{
      this.connecter=false;
      this.deconnecter=true;
      this.route.navigate(["/questions"]);
     }
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
  chargementpage(){
    if (localStorage.getItem('token')) {
  //    alert("dans le if connecter"+this.connecter);
    //  alert("dans le if deconnecter"+this.deconnecter);
      this.connecter=true;   
      this.deconnecter=false;     
      }
      else{
      //  alert("dans le else connecter"+this.connecter)    
      }
      // console.log(this.connecter);
      // if (this.connecter==false) {
      //   this.connecter=true;
      // }
      // else{
      //   console.log(this.connecter);
        
      // }
      // console.log(this.connecter);
      
    }
    // else{
    //   this.route.navigate(["/"])
    // }
 // }
}
