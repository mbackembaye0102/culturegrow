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
import { DetailuserComponent } from './pages/mbacke/detailuser/detailuser.component';
import { GoogleformComponent } from './pages/googleform/googleform.component';
import { DetailcollaborateurComponent } from './pages/admin/collaborateur/detailcollaborateur/detailcollaborateur.component';
import { SessionsComponent } from './pages/admin/sessions/sessions/sessions.component';
import { ValidationsessionsComponent } from './pages/admin/sessions/validationsessions/validationsessions.component';
import { UserteamstructureComponent } from './pages/admin/structure/userteamstructure/userteamstructure.component';
import { AdduserteamstructureComponent } from './pages/admin/structure/adduserteamstructure/adduserteamstructure.component';
import { ListteamComponent } from './pages/admin/evaluations/listteam/listteam.component';
import { EvaluationteamComponent } from './pages/admin/evaluations/evaluationteam/evaluationteam.component';
import { DetailsessioncollaborateurComponent } from './pages/admin/collaborateur/detailsessioncollaborateur/detailsessioncollaborateur.component';
import { GrowComponent } from './pages/admin/grow/grow.component';
import { AuthGuardService } from './service/auth-guard.service';

const routes: Routes = [
  {path:'',component:LoginComponent,pathMatch: 'full'},
  {path:'login',component:LoginComponent},
  {path:'grow',component:GrowComponent,canActivate:[AuthGuardService]},
  {path:'collaborateur',component:ListecollaborateurComponent,canActivate:[AuthGuardService]},
  {path:'collaborateur/add',component:AjoutcollaborateurComponent,canActivate:[AuthGuardService]},
  {path:'collaborateur/detail/:id',component:DetailcollaborateurComponent,canActivate:[AuthGuardService]},
  {path:'detailusersession',component:DetailsessioncollaborateurComponent,canActivate:[AuthGuardService]},
  {path:'collaborateur/update/:id',component:ModifcollaborateurComponent,canActivate:[AuthGuardService]},
  {path:'structure',component:ListestructureComponent,canActivate:[AuthGuardService]},
  {path:'structure/add',component:AjoutstructureComponent,canActivate:[AuthGuardService]},
  {path:'onestructure/:id',component:OnestructureComponent,canActivate:[AuthGuardService]},
  {path:'structure/addteam/:id',component:AddteamstructureComponent,canActivate:[AuthGuardService]},
  {path:'structure/update/:id',component:ModifcollaborateurComponent,canActivate:[AuthGuardService]},
  {path:'onestructure/user/:id',component:UserteamstructureComponent,canActivate:[AuthGuardService]},
  {path:'adduserteam/:id',component:AdduserteamstructureComponent,canActivate:[AuthGuardService]},
  {path:'team/add',component:AjoutteamComponent,canActivate:[AuthGuardService]},
  {path:'team',component:ListeteamComponent,canActivate:[AuthGuardService]},
  {path:'team/user/:id',component:ModifteamComponent,canActivate:[AuthGuardService]},
  {path:'mentor',component:HomeComponent,canActivate:[AuthGuardService]},
  {path:"test",component:TestComponent,canActivate:[AuthGuardService]},
  {path:"questions",component:QuestionsComponent,canActivate:[AuthGuardService]},
  {path:"mbacke",component:ListeComponent,canActivate:[AuthGuardService]},
  {path:"mbackedetail",component:DetailuserComponent,canActivate:[AuthGuardService]},
  {path:"googleform",component:GoogleformComponent,canActivate:[AuthGuardService]},
  {path:"session",component:SessionsComponent,canActivate:[AuthGuardService]},
  {path:"validationsession/:id",component:ValidationsessionsComponent,canActivate:[AuthGuardService]},
  {path:'evaluation',component:ListteamComponent,canActivate:[AuthGuardService]},
  {path:'evaluation/:id',component:EvaluationteamComponent,canActivate:[AuthGuardService]},
  {path:'evaluation/:id',component:DetailsessioncollaborateurComponent,canActivate:[AuthGuardService]},
];

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule { }
