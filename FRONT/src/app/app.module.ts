import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppRoutingModule } from './app-routing.module';
import { AppComponent } from './app.component';
import { LoginComponent } from './login/login.component';
import {ReactiveFormsModule,FormsModule} from '@angular/forms';
import { HttpClientModule, HTTP_INTERCEPTORS } from '@angular/common/http';
import {AuthService} from './service/auth.service';
import { BrowserAnimationsModule } from '@angular/platform-browser/animations';
import { TestComponent } from './test/test.component';
import { InterceptorService } from './interceptor.service';

import { FooterComponent } from './layout/footer/footer.component';
import { SidemenuComponent } from './layout/sidemenu/sidemenu.component';
import { HeaderComponent } from './layout/header/header.component';
import { ListecollaborateurComponent } from './pages/admin/collaborateur/listecollaborateur/listecollaborateur.component';
import { AjoutcollaborateurComponent } from './pages/admin/collaborateur/ajoutcollaborateur/ajoutcollaborateur.component';
import { ModifcollaborateurComponent } from './pages/admin/collaborateur/modifcollaborateur/modifcollaborateur.component';
import { ModifstructureComponent } from './pages/admin/structure/modifstructure/modifstructure.component';
import { AjoutstructureComponent } from './pages/admin/structure/ajoutstructure/ajoutstructure.component';
import { ListestructureComponent } from './pages/admin/structure/listestructure/listestructure.component';
import { ListeteamComponent } from './pages/admin/team/listeteam/listeteam.component';
import { AjoutteamComponent } from './pages/admin/team/ajoutteam/ajoutteam.component';
import { ModifteamComponent } from './pages/admin/team/modifteam/modifteam.component';
import { AjoutposteComponent } from './pages/admin/poste/ajoutposte/ajoutposte.component';
import { ListeposteComponent } from './pages/admin/poste/listeposte/listeposte.component';
import { HomeComponent } from './pages/mentor/home/home.component';
import { OnestructureComponent } from './pages/admin/structure/onestructure/onestructure.component';
import { AddteamstructureComponent } from './pages/admin/structure/addteamstructure/addteamstructure.component';
import { QuestionsComponent } from './pages/questions/questions.component';
import { ListeComponent } from './pages/mbacke/liste/liste.component';
import { DetailuserComponent } from './pages/mbacke/detailuser/detailuser.component';
import { GoogleformComponent } from './pages/googleform/googleform.component';
import { DetailcollaborateurComponent } from './pages/admin/collaborateur/detailcollaborateur/detailcollaborateur.component';
import { SessionsComponent } from './pages/admin/sessions/sessions/sessions.component';
import { ValidationsessionsComponent } from './pages/admin/sessions/validationsessions/validationsessions.component';
import { UserteamstructureComponent } from './pages/admin/structure/userteamstructure/userteamstructure.component';
import { AdduserteamstructureComponent } from './pages/admin/structure/adduserteamstructure/adduserteamstructure.component';
import { ListteamComponent } from './pages/admin/evaluations/listteam/listteam.component';
import { EvaluationteamComponent } from './pages/admin/evaluations/evaluationteam/evaluationteam.component';
import {MaterialModule} from './material/material.module';
import { DetailsessioncollaborateurComponent } from './pages/admin/collaborateur/detailsessioncollaborateur/detailsessioncollaborateur.component';
import { DiagramComponent } from './pages/admin/collaborateur/diagram/diagram.component';
import { GrowComponent } from './pages/admin/grow/grow.component';
import { ChartsModule } from 'ng2-charts';
import { ModaldateComponent } from './pages/admin/collaborateur/modaldate/modaldate.component';
import { JwtModule } from "@auth0/angular-jwt";
import { AuthGuardService } from './service/auth-guard.service';
export function tokenGetter() {
  return localStorage.getItem("token");
}
@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    TestComponent,
    FooterComponent,
    SidemenuComponent,
    HeaderComponent,
    ListecollaborateurComponent,
    AjoutcollaborateurComponent,
    ModifcollaborateurComponent,
    ModifstructureComponent,
    AjoutstructureComponent,
    ListestructureComponent,
    ListeteamComponent,
    AjoutteamComponent,
    ModifteamComponent,
    AjoutposteComponent,
    ListeposteComponent,
    HomeComponent,
    OnestructureComponent,
    AddteamstructureComponent,
    QuestionsComponent,
    ListeComponent,
    DetailuserComponent,
    GoogleformComponent,
    DetailcollaborateurComponent,
    SessionsComponent,
    ValidationsessionsComponent,
    UserteamstructureComponent,
    AdduserteamstructureComponent,
    ListteamComponent,
    EvaluationteamComponent,
    DetailsessioncollaborateurComponent,
    DiagramComponent,
    GrowComponent,
    ModaldateComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    ReactiveFormsModule,
    FormsModule,
    HttpClientModule,
    BrowserAnimationsModule,
    MaterialModule,
    ChartsModule,
    JwtModule.forRoot({
      config: {
        tokenGetter: tokenGetter,
       // whitelistedDomains: ["example.com"],
       // blacklistedRoutes: ["http://example.com/examplebadroute/"],
      },
    }),
  ],
  entryComponents:[ModaldateComponent],
  providers: [AuthGuardService,AuthService,
    {
    provide:HTTP_INTERCEPTORS,
    useClass:InterceptorService,
    multi:true
  }
],
  bootstrap: [AppComponent]
})
export class AppModule { }
