import { ListeComponent } from './pages/mbacke/liste/liste.component';
import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';
import {LoginComponent} from './login/login.component';
import {TestComponent} from './test/test.component';
import { ListecollaborateurComponent } from './pages/admin/collaborateur/listecollaborateur/listecollaborateur.component';
import { AjoutcollaborateurComponent } from './pages/admin/collaborateur/ajoutcollaborateur/ajoutcollaborateur.component';
import { ModifcollaborateurComponent } from './pages/admin/collaborateur/modifcollaborateur/modifcollaborateur.component';
import { AjoutstructureComponent } from './pages/admin/structure/ajoutstructure/ajoutstructure.component';
import { AjoutteamComponent } from './pages/admin/team/ajoutteam/ajoutteam.component';
import { ListeteamComponent } from './pages/admin/team/listeteam/listeteam.component';
import { ModifteamComponent } from './pages/admin/team/modifteam/modifteam.component';
import { ListestructureComponent } from './pages/admin/structure/listestructure/listestructure.component';
import { HomeComponent } from './pages/mentor/home/home.component';
import { OnestructureComponent } from './pages/admin/structure/onestructure/onestructure.component';
import { AddteamstructureComponent } from './pages/admin/structure/addteamstructure/addteamstructure.component';
import { QuestionsComponent } from './pages/questions/questions.component';
import { AdduserComponent } from './pages/adduser/adduser.component';
import { DetailuserComponent } from './pages/mbacke/detailuser/detailuser.component';
import { GoogleformComponent } from './pages/googleform/googleform.component';
import { DetailcollaborateurComponent } from './pages/admin/collaborateur/detailcollaborateur/detailcollaborateur.component';

const routes: Routes = [
  {path:'',component:LoginComponent},
  {path:'login',component:LoginComponent},
  {path:'collaborateur',component:ListecollaborateurComponent},
  {path:'collaborateur/add',component:AdduserComponent},
  {path:'collaborateur/detail/:id',component:DetailcollaborateurComponent},
  {path:'collaborateur/update/:id',component:ModifcollaborateurComponent},
  {path:'structure',component:ListestructureComponent},
  {path:'structure/add',component:AjoutstructureComponent},
  {path:'onestructure/:id',component:OnestructureComponent},
  {path:'structure/addteam/:id',component:AddteamstructureComponent},
  {path:'structure/update/:id',component:ModifcollaborateurComponent},
  {path:'team/add',component:AdduserComponent},
  {path:'team/:id',component:ListeteamComponent},
  {path:'team/update/:id',component:ModifteamComponent},
  {path:'mentor',component:HomeComponent},
  {path:"test",component:TestComponent},
  {path:"questions",component:QuestionsComponent},
  {path:"mbacke",component:ListeComponent},
  {path:"mbackedetail",component:DetailuserComponent},
  {path:"googleform",component:GoogleformComponent},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
