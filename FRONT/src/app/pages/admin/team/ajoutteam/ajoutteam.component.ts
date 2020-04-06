import { AdminService } from './../../../../service/admin.service';
import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';
import { FormGroup, FormControl } from '@angular/forms';

@Component({
  selector: 'app-ajoutteam',
  templateUrl: './ajoutteam.component.html',
  styleUrls: ['./ajoutteam.component.scss']
})
export class AjoutteamComponent implements OnInit {
  public image="assets/defaut.png";
  public fileToUpload: File=null;
  constructor(private auth:AuthService,private admin:AdminService) { }

  ngOnInit() {
   // this.auth.chargementpage()
 //  console.log(this.admin.idteam);
   
  }
  team= new FormGroup({
    nom: new FormControl('')
  })
  save(donner){
    this.admin.addgrowteam(donner,this.fileToUpload).subscribe(
      res=>{console.log(res);
      },
      error=>{console.log(error);
      }
    )
  }
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
