import { FormGroup, FormControl, Validators } from '@angular/forms';
import { Component, OnInit } from '@angular/core';
export interface ModelData {
  label:string;
  type:string;

}
@Component({
  selector: 'app-googleform',
  templateUrl: './googleform.component.html',
  styleUrls: ['./googleform.component.scss']
})
export class GoogleformComponent implements OnInit {

  constructor() { }
public taille=[];
public data=[];
public nbr=0;
public moins=false;
public model={
  label:"a",
  type:"a"
};
  ngOnInit() {
    this.taille.push(this.model);
  }
  form= new FormGroup({
    label: new FormControl('',Validators.required),
    type: new FormControl('',Validators.required),
    types: new FormControl('',Validators.required),
  })
  next(){
    //this.taille.push("a");
    
    this.nbr++;
    this.moins=true;
    this.addData();
  }
  addData(){
    let a=this.form.value;
    this.taille[this.nbr-1]=a;
    this.data.push(a);
    this.taille.push("a");
    this.form.reset()
    console.log(this.taille);
    
    console.log(this.data);
    
  }
  previous(){
    this.taille.pop();
    this.nbr--;
    console.log(this.taille);
    console.log(this.data);
    console.log(this.taille.length);
    
    if (this.nbr==0) {
      this.moins=false
    }
  }
}
