import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {LoginComponent} from './login/login.component';
import {TestComponent} from './test/test.component';
import { ListstructureComponent } from './structure/liststructure/liststructure.component';
import { AddstructureComponent } from './structure/addstructure/addstructure.component';
import { OnestructureComponent } from './structure/onestructure/onestructure.component';
import { ListuserComponent } from './user/listuser/listuser.component';
import { AdduserComponent } from './user/adduser/adduser.component';
import { OneuserComponent } from './user/oneuser/oneuser.component';

const routes: Routes = [
  {path:'',component:LoginComponent},
  {path:'structure',component:ListstructureComponent},
  {path:'structure/new',component:AddstructureComponent},
  {path:'onestructure/:id',component:OnestructureComponent},
  {path:'grow/users',component:ListuserComponent},
  {path:'grow/add/users',component:AdduserComponent},
  {path:'grow:user/:id',component:OneuserComponent},
  {path:"test",component:TestComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
