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
  constructor(private http: HttpClient,private route:Router) { }
  logger(data){
    return this.http.post(this.urllogin , data , {observe:'response'})
  }
  infosconnecter(){
    return this.http.post(this.urlInfos  , {observe:'response'})
  }
  enregistrementToken(jwtToken : string){ 
    localStorage.setItem('token',jwtToken);
    this.jwt=jwtToken;
    let jwtHelper = new JwtHelperService();
    let objet= jwtHelper.decodeToken(this.jwt);
    localStorage.setItem('role',objet.roles[0]);
    this.connecter=true;
    this.redirection();
  }
  redirection(){
    this.role=localStorage.getItem('role');
    if (this.role==="ROLE_ADMIN") {
      this.route.navigate(['/collaborateur']);
    }
    else if(this.role==="ROLE_MENTOR"){
      this.route.navigate(['/mentor']);
    }
    else{
      this.route.navigate(['/mbackedetail']);
    }
  }
  logout(){
    localStorage.removeItem('token');
    localStorage.removeItem('role');
    this.connecter=false;
    this.route.navigate(["/login"])
  }
  getToken(){
    return this.jwt=localStorage.getItem('token');
  }
  chargementpage(){
    let good=this.getToken();
      if (good) {
        this.connecter=true;
      }
      else{
        this.logout();
      }
    }
}
