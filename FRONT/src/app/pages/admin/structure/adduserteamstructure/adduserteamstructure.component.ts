import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';
import { ActivatedRoute, Router } from '@angular/router';
import { FormGroup, FormControl } from '@angular/forms';
import Swal from 'sweetalert2';

@Component({
  selector: 'app-adduserteamstructure',
  templateUrl: './adduserteamstructure.component.html',
  styleUrls: ['./adduserteamstructure.component.scss']
})
export class AdduserteamstructureComponent implements OnInit {
  public id:any;
  public image="assets/defaut.png";
  public fileToUpload: File=null;
  public mentor:any;
  public message:any;
  constructor(private admin:AdminService,private activeroute:ActivatedRoute,private route:Router) { }

  ngOnInit() {
    this.id=this.activeroute.snapshot.params['id'];
    this.admin.listementor().subscribe(
      res=>{console.log(res);
        this.mentor=res;
      },
      error=>{console.log(error);
      }
    )
  }
  user=new FormGroup({
    prenom:new FormControl(''),
    nom:new FormControl(''),
    telephone:new FormControl(''),
    email:new FormControl(''),
    nomtuteur:new FormControl(''),
    telephonetuteur:new FormControl(''),
    id:new FormControl(''),
    poste:new FormControl(''),
    mentor:new FormControl('')
  })
  save(donner){
    donner.id=this.id;
    this.admin.saveuserteam(donner,this.fileToUpload).subscribe(
      res=>{console.log(res.body);
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
          this.route.navigate(['/onestructure/user',this.id])
        }
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
