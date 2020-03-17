import { AuthService } from './../../../../service/auth.service';
import { Component, OnInit } from '@angular/core';
import { AdminService } from 'src/app/service/admin.service';

@Component({
  selector: 'app-listestructure',
  templateUrl: './listestructure.component.html',
  styleUrls: ['./listestructure.component.scss']
})
export class ListestructureComponent implements OnInit {
  public structuremodel:any;
  constructor(private admin: AdminService,private auth:AuthService) { }
  
  ngOnInit() {
    this.hello();
  }
  hello(){
    this.admin.listestructure().subscribe(
      res=>{
        console.log(res);
        this.structuremodel=res;
        
      },
      error=>{console.log(error);}
      
    )
  }
}
