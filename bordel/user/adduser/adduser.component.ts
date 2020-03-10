import { StructureService } from '../../structuress/structure.service';
import { Component, OnInit,  } from '@angular/core';
import { FormGroup, FormControl } from '@angular/forms';

@Component({
  selector: 'app-adduser',
  templateUrl: './adduser.component.html',
  styleUrls: ['./adduser.component.scss']
})
export class AdduserComponent implements OnInit {
  public rien:boolean=true;
  public cache:boolean=true;
  
  constructor(private monService:StructureService) { }
  suivant=false;
  public unTab = 2;
  public list=[]
  public nbrFichier = 0;
  ngOnInit() {
  }
user= new FormGroup({
  prenom: new FormControl(''),
  nom: new FormControl(''),
  telephone:new FormControl!(''),
  email:new FormControl(''),
  profil: new FormControl(''),
  teams: new FormControl('')
})
  plusteam(){
    this.cache=false;
    this.list.push("a");
    this.nbrFichier++;
  }
  plusteam1(){
    this.list.push("a");
    this.nbrFichier++;
  }
  onAddFile() {
   // this.unTab.push("");
    this.nbrFichier++;
  }
save(donner){
  console.log(donner);
  this.monService.saveuser(donner.value).subscribe(
    res=>{console.log(res);
    },
    error=>{console.log(error);
    }
  )
}
}
