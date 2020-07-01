import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';
import { MatDialogRef } from '@angular/material/dialog';

@Component({
  selector: 'app-modaldate',
  templateUrl: './modaldate.component.html',
  styleUrls: ['./modaldate.component.scss']
})
export class ModaldateComponent implements OnInit {
  public item=false;
  public date:any;
  constructor(private admin:AdminService,public dialogRef: MatDialogRef<ModaldateComponent>) { }

  ngOnInit() {
    this.admin.alldate().subscribe(
      res=>{
        console.log(res.body);
        this.admin.lesdate=res.body;
        for (let index = 0; index < this.admin.lesdate.length; index++) {
          this.admin.lesdate[index].etat=false          
        }
        console.log(this.admin.lesdate);
        
      },
      error=>{console.log(error);
      }
    )
  }
  choix(id){
    for (let index = 0; index < this.admin.lesdate.length; index++) {
      if (this.admin.lesdate[index].id==id) {
        if (this.admin.lesdate[index].etat==false) {
         this.admin.lesdate[index].etat=true;
        }
        else{
         this.admin.lesdate[index].etat=false;
        }
       
      }
 
    }
     
   }
   valider(){
     this.dialogRef.close();
   }
}
