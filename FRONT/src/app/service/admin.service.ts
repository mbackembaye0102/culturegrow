import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class AdminService {
  private urlsaveuser:string="http://127.0.0.1:8000/saveuser";
  private urllistuser:string="http://127.0.0.1:8000/listuser";
  private urllisteteamgrow:string="http://127.0.0.1:8000/growteam";
  constructor(private http: HttpClient) { }
 saveuser(data){
    return this.http.post(this.urlsaveuser , data , {observe:'response'})
  }
  listuser(){
    return this.http.post(this.urllistuser , {observe:'response'})
  }
  listeteamgrow(){
    return this.http.post(this.urllisteteamgrow,{observe:'response'})
  }
}
