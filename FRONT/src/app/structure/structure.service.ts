import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';

@Injectable({
  providedIn: 'root'
})
export class StructureService {
  private urlsavestructure:string="http://127.0.0.1:8000/addstructure";
  private urlsaveuser:string="http://127.0.0.1:8000/saveuser";
  private urllistuser:string="http://127.0.0.1:8000/listuser";
  private urlsavepromostructure:string="http://127.0.0.1:8000/addpromostructure";
  private urlliststructure:string="http://127.0.0.1:8000/liststructure";
  constructor(private http: HttpClient) { }
  savestructure(data){
    return this.http.post(this.urlsavestructure , data , {observe:'response'})
  }
  liststructure(){
    return this.http.post(this.urlliststructure  , {observe:'response'})
  }
  savespromotructure(data){
    return this.http.post(this.urlsavepromostructure , data , {observe:'response'})
  }
  saveuser(data){
    return this.http.post(this.urlsaveuser , data , {observe:'response'})
  }
  listuser(){
    return this.http.post(this.urllistuser , {observe:'response'})
  }
}
