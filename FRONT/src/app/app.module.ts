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
// import { AddstructureComponent } from './structure/addstructure/addstructure.component';
// import { ListstructureComponent } from './structure/liststructure/liststructure.component';
// import { OnestructureComponent } from './structure/onestructure/onestructure.component';
// import { ListuserComponent } from './user/listuser/listuser.component';
// import { AdduserComponent } from './user/adduser/adduser.component';
// import { OneuserComponent } from './user/oneuser/oneuser.component';
// import { AddpromoComponent } from './structure/addpromo/addpromo.component';
// import { OrganisationComponent } from './grow/organisation/organisation.component';
// import { AddTeamComponent } from './grow/add-team/add-team.component';
// import { AddTeamFunctionComponent } from './grow/add-team-function/add-team-function.component';
// import { HomeComponent } from './mentor/home/home.component';
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
// import { ListecollaborateurComponent } from './pages/admin/listecollaborateur/listecollaborateur.component';
// import { AjoutcollaborateurComponent } from './pages/admin/ajoutcollaborateur/ajoutcollaborateur.component';
// import { ModifcollaborateurComponent } from './pages/admin/modifcollaborateur/modifcollaborateur.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    TestComponent,
    // AddstructureComponent,
    // ListstructureComponent,
    // OnestructureComponent,
    // ListuserComponent,
    // AdduserComponent,
    // OneuserComponent,
    // AddpromoComponent,
    // OrganisationComponent,
    // AddTeamComponent,
    // AddTeamFunctionComponent,
    // HomeComponent,
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
    // ListecollaborateurComponent,
    // AjoutcollaborateurComponent,
    // ModifcollaborateurComponent,
  ],
  imports: [
    BrowserModule,
    AppRoutingModule,
    ReactiveFormsModule,
    FormsModule,
    HttpClientModule,
    BrowserAnimationsModule,
  ],
  providers: [AuthService,
    {
    provide:HTTP_INTERCEPTORS,
    useClass:InterceptorService,
    multi:true
  }
],
  bootstrap: [AppComponent]
})
export class AppModule { }
