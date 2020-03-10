import { Component, OnInit } from '@angular/core';
import {FormControl,FormGroup,Validators} from '@angular/forms';
import { AuthService } from '../service/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.scss']
})
export class LoginComponent implements OnInit {

  constructor(private authentification :AuthService) { }

  ngOnInit() {
    console.log("login");
    
  }
  token:any;
  addsys(donner:any){
    console.log(donner);
    this.authentification.logger(donner).subscribe(
      res=>{
        this.authentification.connecter=true;
        this.token=res.body
        this.authentification.enregistrementToken(this.token.token);
      },
      error=>{console.log(error);
        console.log("rien");
        
      }
    )
  }
  utilisateur= new FormGroup({
    username: new FormControl('',Validators.required),
    password: new FormControl('',Validators.required)
  })
}
