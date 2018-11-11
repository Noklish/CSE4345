import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';

import { AppComponent } from './app.component';
import { SignupComponent } from './signup/signup.component';
import { SigninComponent } from './signin/signin.component';
import { HeaderComponent } from './header/header.component';
import { ChatBoardComponent } from './chat-board/chat-board.component';
import { CommentsComponent } from './chat-board/comments/comments.component';
import { HomeComponent } from './home/home.component';
import { ProfileUpdateComponent } from './profile-update/profile-update.component';

@NgModule({
  declarations: [
    AppComponent,
    SignupComponent,
    SigninComponent,
    HeaderComponent,
    ChatBoardComponent,
    CommentsComponent,
    HomeComponent,
    ProfileUpdateComponent
  ],
  imports: [
    BrowserModule
  ],
  providers: [],
  bootstrap: [AppComponent]
})
export class AppModule { }
