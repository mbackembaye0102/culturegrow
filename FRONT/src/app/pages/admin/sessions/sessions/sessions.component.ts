import { AdminService } from './../../../../service/admin.service';
import { Component, OnInit } from '@angular/core';

@Component({
  selector: 'app-sessions',
  templateUrl: './sessions.component.html',
  styleUrls: ['./sessions.component.scss']
})
export class SessionsComponent implements OnInit {
  public structuremodel:any;
  constructor(private admin: AdminService) { }

  ngOnInit() {
    this.admin.titrepage="SESSION";
    this.admin.allstructure().subscribe(
      res=>{
        console.log("sdds");
        
        console.log(res);
        this.structuremodel=res;
        
      },
      error=>{console.log(error);}
      
    )
  }

}
