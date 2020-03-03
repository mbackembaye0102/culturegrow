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
import { AddstructureComponent } from './structure/addstructure/addstructure.component';
import { ListstructureComponent } from './structure/liststructure/liststructure.component';
import { OnestructureComponent } from './structure/onestructure/onestructure.component';
import { ListuserComponent } from './user/listuser/listuser.component';
import { AdduserComponent } from './user/adduser/adduser.component';
import { OneuserComponent } from './user/oneuser/oneuser.component';

@NgModule({
  declarations: [
    AppComponent,
    LoginComponent,
    TestComponent,
    AddstructureComponent,
    ListstructureComponent,
    OnestructureComponent,
    ListuserComponent,
    AdduserComponent,
    OneuserComponent,
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
