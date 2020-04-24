import { Router } from '@angular/router';
import { FormGroup, FormControl } from '@angular/forms';
import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';
import { Teampromo } from 'src/app/model/teampromo.model';
import Swal from 'sweetalert2'
import {Message} from '../../../../model/message.model'

@Component({
  selector: 'app-ajoutcollaborateur',
  templateUrl: './ajoutcollaborateur.component.html',
  styleUrls: ['./ajoutcollaborateur.component.scss']
})
export class AjoutcollaborateurComponent implements OnInit {
  url={addcollaborateur:'/collaborateur/add',addteam:'/team/add'}
  public rien:boolean=true;
  public cache:boolean=true;
  public image="assets/defaut.png";
  suivant=false;
  public unTab = 2;
  public list=[""];
  public listposte=[""]
  public nbrFichier = 0;
  public nbrFichierpost=0;
  public taille=0;
  public nombreteam:number=0;
  public nombreposte:number=0;
  public team:Teampromo;
  public job:Teampromo;
  public addgrow:boolean=false;
  public addteam:boolean=false;
  public fileToUpload: File=null;
  public message:any;
  public postename:string='';
  public teamtable=[];
  constructor(private router: Router,private auth:AuthService,public admin:AdminService) { }

  ngOnInit() {
    this.admin.titrepage="AJOUT COLLABORATEUR";
    this.admin.listeteamgrow().subscribe(
      res=>{
        this.team=res;
      },
      error=>{
        console.log(error);
      }
    )
    this.admin.listepostegrow().subscribe(
      res=>{
        this.job=res;
      },
      error=>{
        console.log(error);
      }
      )

  }
  user= new FormGroup({
    prenom: new FormControl(''),
    nom: new FormControl(''),
    telephone:new FormControl!(''),
    email:new FormControl(''),
    profil: new FormControl(''),
    team0: new FormControl(''),
    team:new FormControl(),
    taille: new FormControl(''),
    tailleposte: new FormControl(''),
    poste0: new FormControl(''),
    image:new FormControl('')
  });
    plusteam(){
      this.nombreteam++
      let e="team"+this.nombreteam;
      this.user.addControl(e,new FormControl());
      this.list.push("a");
      this.nbrFichier++;
      this.taille++;
    }
    plusposte(){
      let er:string="poste"+this.nombreposte;
     console.log(this.user.get(er));
      
     // console.log(this.user.value);
    //  
      this.nombreposte++;
      let e="poste"+this.nombreposte;
      this.user.addControl(e,new FormControl());
      this.listposte.push("a");
      this.nbrFichierpost++;
    }
    onAddFile() {
      this.nbrFichier++;
    }
  save(donner){
    console.log(donner);
    console.log(this.taille);
    console.log('nombreposte='+this.nombreposte);
    donner.tailleposte=this.nombreposte;
    donner.taille=this.taille;
   
    //console.log(donner);
    for (let index = 0; index <=donner.taille; index++) {
      let er:string="team"+index;
      let a=this.user.get(er).value;
      this.teamtable.push(a);
    }
    for (let index = 0; index <=donner.tailleposte; index++) {
      let er:string="poste"+index;
      let a=this.user.get(er).value;
      this.postename=this.postename+','+a;
    }
    donner.poste=this.postename;
    donner.team=this.teamtable;
    console.log(donner);
    //console.log(this.user.value);
    this.admin.saveuser(donner,this.fileToUpload).subscribe(
      res=>{console.log(res);
      this.message=res.body;
      if (this.message.status==201) {
        Swal.fire({
          icon: 'error',
          title: 'Oops...',
          text: this.message.message,
        })
      }
      else{
        Swal.fire({
          icon: 'success',
          title: 'BRAVO',
          text: this.message.message,
        })
        this.router.navigate(['/collaborateur'])
      }

      },
      error=>{console.log(error);
      }
    )
  }
  userteam=new FormGroup({
    prenom: new FormControl(''),
    nom: new FormControl(''),
    telephone: new FormControl(''),
    email: new FormControl(''),
    poste: new FormControl(''),
    id: new FormControl(''),
  })
  handleFileInputPP(file: FileList) {
    console.log(file);
    this.fileToUpload=file.item(0)
     var reader = new FileReader();
    reader.onload = (event: any) => {
      this.image = event.target.result;
    }
    reader.readAsDataURL(this.fileToUpload);
  }

}
