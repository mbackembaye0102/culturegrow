import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { JwtHelperService } from "@auth0/angular-jwt";
import { Router } from '@angular/router';
import {Users} from '../model/user.model';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  public url:string="http://127.0.0.1:8000/";
  private urllogin:string="login";
  private urlInfos:string="infos/user";

  jwt: string;
  role:any;
  public admin=false;
  public isevaluation:boolean=false;
  public typeevaluation:string=null;
  public concerner:any=null;
  public user=false;
  public connecter=false;
  public utilisateur:any;

  constructor(private http: HttpClient,private route:Router) { }
  logger(data){
    return this.http.post(this.url+this.urllogin , data , {observe:'response'})
  }
  infosconnecter(){
    return this.http.post(this.url+this.urlInfos  , {observe:'response'})
  }
  enregistrementToken(jwtToken : string){ 
    localStorage.setItem('token',jwtToken);
    this.jwt=jwtToken;
    let jwtHelper = new JwtHelperService();
    let objet= jwtHelper.decodeToken(this.jwt);
    localStorage.setItem('role',objet.roles[0]);
    this.chargementpage()
  //  this.connecter=true;
    this.redirection();
  }
  redirection(){
    this.role=localStorage.getItem('role');
    if (this.role==="ROLE_ADMIN") {
      //this.admin=true;
      this.route.navigate(['/collaborateur']);
    }
    else if(this.role==="ROLE_MENTOR"){
    //  this.user=true;
      this.route.navigate(['/mentor']);
    }
    else{
      this.route.navigate(['/evaluation']);
      this.user=true;

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
        this.infosconnecter().subscribe(
          res=>{
            console.log(res);
            this.utilisateur=res;
            if (this.utilisateur.all=="good") {
            //  alert("Evaluation par Team"+this.utilisateur.teamevaluer);
              this.isevaluation=true;
            }
            else if(this.utilisateur.all=="bad"){
              if (this.utilisateur.teamevaluer=="rien") {
               // alert("Pas D'évaluation")
                this.isevaluation=false;
              }
              else{
               // alert("Evaluation par Team"+this.utilisateur.teamevaluer);
                this.isevaluation=true;
              }
            }
          },
          error=>{
            console.log(error);
            
          })
          this.role=localStorage.getItem('role');
          if (this.role==="ROLE_ADMIN") {
            this.admin=true;
            this.user=false;
          }
          else{
            this.user=true;
            this.admin=false;
          }
          this.connecter=true;
      }
      else{
        this.logout();
      }
    }
}
