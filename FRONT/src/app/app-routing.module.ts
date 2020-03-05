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
import { AddpromoComponent } from './structure/addpromo/addpromo.component';
import { OrganisationComponent } from './grow/organisation/organisation.component';
import { AddTeamComponent } from './grow/add-team/add-team.component';
import { AddTeamFunctionComponent } from './grow/add-team-function/add-team-function.component';

const routes: Routes = [
  {path:'',component:LoginComponent},
  {path:'structure',component:ListstructureComponent},
  {path:'structure/new',component:AddstructureComponent},
  {path:'onestructure/:id',component:OnestructureComponent},
  {path:'grow/users',component:ListuserComponent},
  {path:'grow/add/users',component:AdduserComponent},
  {path:'grow:user/:id',component:OneuserComponent},
  {path:'promo/add',component:AddpromoComponent},
  {path:'grow/organisation',component:OrganisationComponent},
  {path:'grow/addteam',component:AddTeamComponent},
  {path:'grow/addteamfunction',component:AddTeamFunctionComponent},
  {path:"test",component:TestComponent}
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
